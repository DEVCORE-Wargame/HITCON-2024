#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
    seteuid(0);
    setegid(0);
    setuid(0);
    setgid(0);

    FILE *fp = fopen("/flag.txt", "r");
    if (fp == NULL) {
        perror("Error opening file");
        return EXIT_FAILURE;
    }

    char flag[256] = { 0 };
    if (fgets(flag, sizeof(flag), fp) == NULL) {
        perror("Error reading file");
        fclose(fp);
        return EXIT_FAILURE;
    }

    fclose(fp);
    puts(flag);

    return EXIT_SUCCESS;
}