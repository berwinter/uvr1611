//
//  main.c
//  tt
//
//  Created by Bertram Winter on 27.10.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <stdint.h>

#include "helper.h"
#include "logger.h"
#include "uvrconnection.h"


int main (int argc, char * argv[])
{
    parseOptions(argc, argv);
    createPid();
    initSyslog();
    
    int startAddress, endAddress, count;
    check_mode();
    
    if(bActual)
    {
        recv_latest();
    }
    else
    {
        printSyslog("Start reading:...");
        count = check_header(&startAddress, &endAddress);

        printSyslog("%d new datasets to read",count);
        recv_data(startAddress, endAddress, count);
        
        reset_data();
    }
    
    closeSyslog();
    remove(PID_FILE);
    return 0;
}
