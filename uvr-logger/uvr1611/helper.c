//
//  helper.c
//  tt
//
//  Created by Bertram Winter on 03.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <stdint.h>
#include <ctype.h>
#include <stdlib.h>
#include <unistd.h>

#include "helper.h"

int tcpPort=40000;
int bActual=0;
int bReset=0;
char * hostname;

uint8_t checksum8(uint8_t * data, int length )
{
    uint8_t retval=0;
    for (int i=0; i<length; i++)
        retval += data[i];
    
    return retval;
}

void parseOptions(int argc, char ** argv)
{
    int c;
    while ((c = getopt (argc, argv, "arp:")) != -1)
    {
        switch(c)
        {
            case 'a':
                bActual = 1;
                break;
            case 'r':
                bReset = 1;
                break;
            case 'p':
                // parse port number
                tcpPort = atoi(optarg);
                break;
            case '?':
                if (optopt == 'p')
                    fprintf (stderr, "Option -%c requires an argument.\n", optopt);
                else if (isprint (optopt))
                    fprintf (stderr, "Unknown option `-%c'.\n", optopt);
                else
                    fprintf (stderr,
                             "Unknown option character `\\x%x'.\n",
                             optopt);
                exit(ERROR);
            default:
                abort();
        }
    }
    
    hostname = argv[optind];
    
}
