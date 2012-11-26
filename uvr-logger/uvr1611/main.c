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
#include "uvrconnection.h"


int main (int argc, char * argv[])
{
    parseOptions(argc, argv);

    int startAddress, endAddress, count;
    check_mode();
    
    if(bActual)
    {
        recv_latest();
    }
    else
    {
        count = check_header(&startAddress, &endAddress);
        
        recv_data(startAddress, endAddress, count);
        
        reset_data();
    }
    return 0;
}
