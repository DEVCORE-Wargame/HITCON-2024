#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main(int argc, char *argv[]) {
    seteuid(0);
    setegid(0);
    setuid(0);
    setgid(0);

    FILE *fp = fopen("/flag.txt", "r");
    if (fp == NULL) {
        perror("Error opening file");
        return EXIT_FAILURE;
    }

    if(argc < 5) {
        printf("Usage: %s give me the flag\n", argv[0]);
        return EXIT_FAILURE;
    }

    if ((strcmp(argv[1], "give") | strcmp(argv[2], "me") | strcmp(argv[3], "the") | strcmp(argv[4], "flag")) != 0) {
        perror("Error reading argv");
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
