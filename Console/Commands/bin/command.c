#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
int main(int argc,char **argv) {

    int ret= system("/usr/sbin/service mysql restart");
    if(ret!=0)
    {
        printf("error");
    }
    else
    {
        printf("success");
    }

    return 0;
}
