using System.Diagnostics;
using System.Net;
using System.Security.Cryptography;
using System.Text;

namespace VArchive
{
    internal class Program
    {
        public static string GetYouTubeVideoId(string url)
        {
            if (url.Contains("v="))
            {
                // youtube.com/watch?v=<id>
                var parts = url.Split('?', 2);
                if (parts.Length != 2)
                {
                    return "";
                }
                parts = parts[1].Split('&');
                foreach (var part in parts)
                {
                    if (part.StartsWith("v="))
                    {
                        return part.Substring(2);
                    }
                }
                return "'";
            }
            else
            {
                // youtu.be/<id>
                string path = url.Split('?', 2)[0];
                return path.Split('/').Last();
            }
        }

        public static string GetMD5Hash(string str)
        {
            using (MD5 md5 = MD5.Create())
            {
                byte[] strBytes = Encoding.UTF8.GetBytes(str);
                byte[] hashBytes = md5.ComputeHash(strBytes);
                return Convert.ToHexString(hashBytes);
            }
        }

        static void Main(string[] args)
        {
            string listenUri = "http://+:8000/";

            HttpListener listener = new HttpListener();
            listener.Prefixes.Add(listenUri);
            listener.Start();
            Console.WriteLine("Listening for connections on {0}", listenUri);

            Directory.CreateDirectory("/archives");

            while (true)
            {
                HttpListenerContext context = listener.GetContext();
                HttpListenerRequest request = context.Request;
                HttpListenerResponse response = context.Response;

                try
                {
                    string? url = request.QueryString.Get("url");
                    if (string.IsNullOrEmpty(url))
                    {
                        byte[] result = Encoding.UTF8.GetBytes("<form method=\"GET\"><input type=\"text\" name=\"url\" placeholder=\"https://www.youtube.com/watch?v=iU0QOcM7UnU\" /><button type=\"submit\" >save!</button></form>");
                        response.ContentType = "text/html";
                        response.OutputStream.Write(result, 0, result.Length);
                    }
                    else
                    {
                        string saveId = GetMD5Hash(url);
                        string videoId = GetYouTubeVideoId(url);
                        string outputPath = string.Format("/archives/{0}.mp4", saveId);

                        if (!string.IsNullOrEmpty(videoId))
                        {

                            if (File.Exists(outputPath))
                            {
                                byte[] result = Encoding.UTF8.GetBytes(string.Format("Start to download {0}", url));
                                response.OutputStream.Write(result, 0, result.Length);
                            }
                            else
                            {
                                string arguments = string.Format("\"https://www.youtube.com/watch?v={0}\" -o \"{1}\"", videoId, outputPath);
                                ProcessStartInfo startInfo = new ProcessStartInfo();
                                startInfo.WorkingDirectory = "/archives";
                                startInfo.FileName = "youtube-dl";
                                startInfo.Arguments = arguments;
                                Process process = new Process();
                                process.StartInfo = startInfo;
                                process.Start();
                                byte[] result = Encoding.UTF8.GetBytes(string.Format("Start to download {0}", url));
                                response.OutputStream.Write(result, 0, result.Length);
                            }
                        }
                    }
                    response.Close();
                }
                catch (Exception e)
                {
                    Console.WriteLine(e);
                }
                finally
                {
                    response.Close();
                }
            }

            listener.Close();
        }
    }
}