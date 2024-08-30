# Use the official Golang image to build the Go binary
FROM golang:1.18-alpine AS builder

# Set the Current Working Directory inside the container
WORKDIR /app

# Copy the source code into the container
COPY . .

# Build the Go app
RUN go mod init proxy && go build -o main .

# Start a new stage from scratch
FROM alpine:latest

WORKDIR /root/

# Copy the pre-built binary file from the previous stage
COPY --from=builder /app/main .

# Expose port 8081 to the outside world
EXPOSE 8081

# Command to run the executable
CMD ["./main"]

