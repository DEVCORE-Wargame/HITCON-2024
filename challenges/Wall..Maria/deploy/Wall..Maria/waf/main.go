package main

import (
	"log"
	"io"
	"net/http"
	"os"
	"regexp"
	"strings"
)

func doWAF(r *http.Request) bool {
	var parsed = false
	var isPOST = r.Method == http.MethodPost

	bodyData, _ := io.ReadAll(r.Body)
	r.Body = io.NopCloser(strings.NewReader(string(bodyData)))

	if hasDotDot(string(bodyData)) {
		return false
	}

	if mr, err := r.MultipartReader(); err == nil {
		for {
			parsed = true
			part, err := mr.NextPart()
			if err == io.EOF {
				break
			}
			data, _ := io.ReadAll(part)
			part.Close()
			if !isASCII(part.FormName()) || !isASCII(string(data)) || hasDotDot(string(data)) {
				return false
			}
		}
	} else {
		if err := r.ParseForm(); err != nil {
			return false
		}
		for key, values := range r.Form {
			parsed = true
			if !isASCII(key) || hasDotDot(key) {
				return false
			}
			for _, value := range values {
				if !isASCII(value) || hasDotDot(value) {
					return false
				}
			}
		}
	}

	r.Body = io.NopCloser(strings.NewReader(string(bodyData)))
	return !isPOST || (isPOST && parsed)
}

func hasDotDot(s string) bool {
	dotDotPattern := regexp.MustCompile(`(?i)(\.|%2e){2}`)
	if dotDotPattern.MatchString(s) {
		return true 
	}
	return false
}

func isASCII(s string) bool {
	for i := 0; i < len(s); i++ {
		if s[i] >= 0x7F || s[i] < 0x20{
			return false
		}
	}
	return true
}

func proxyRequest(w http.ResponseWriter, r *http.Request) {
	if !doWAF(r) {
		http.Error(w, "WAF!", http.StatusForbidden)
		return
	}

	backendURL := os.Getenv("BACKEND_URL")
	proxyReq, err := http.NewRequest(r.Method, backendURL+r.RequestURI, r.Body)
	if err != nil {
		http.Error(w, "Internal Server Error", http.StatusInternalServerError)
		return
	}

	for key, values := range r.Header {
		for _, value := range values {
			proxyReq.Header.Set(key, value)
		}
	}

	resp, err := http.DefaultClient.Do(proxyReq)
	if err != nil {
		http.Error(w, "Bad Gateway", http.StatusBadGateway)
		return
	}
	defer resp.Body.Close()

	for key, values := range resp.Header {
		for _, value := range values {
			w.Header().Set(key, value)
		}
	}
	w.WriteHeader(resp.StatusCode)
	io.Copy(w, resp.Body)
}

func main() {
	http.HandleFunc("/", proxyRequest)
	log.Println("Proxy server running on port 80...")
	log.Fatal(http.ListenAndServe(":80", nil))
}
