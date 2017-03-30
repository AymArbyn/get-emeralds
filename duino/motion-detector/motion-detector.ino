/* ======================================================================
 *
 * Green Electronic Technology: Efficient Management of Energy through
 * Responsive and Attentive Live-Data Sourcing (GET:EMERALDS)
 *
 * Filename: motion-detector.ino
 *
 * Written by:
 *   - Arbyn Acosta <arbyn.acosta@ieee.org>
 *   - Christopher John Mata
 *
 * Copyright (c) 2016-2017
 * GET:EMERALDS
 * All Rights Reserved
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * =================================================================== */

#include <EMERALDS_Base.h>
#include <SharpIR.h>

// debugging mode
#define DEBUG_MODE

// networking ids
#define NETWORKID  EMERALDS_NET_ID
#define NODEID     UD_NET_ID
#define RECEIVER   MARC_NET_ID

// create radio instance
RFM69 node = RFM69(FEATHER_RFM69_CS, FEATHER_RFM69_IRQ, IS_RFM69HCW, FEATHER_RFM69_IRQN);

// sensor instances
SharpIR sharp1(A0, 20150);
SharpIR sharp2(A1, 20150);

// function prototype
boolean IR0triggered(void);
boolean IR1triggered(void);

// function variables
static char radiopacket[DATA_MAX] = "EMERALDS FROM UD <-------";
static boolean toSend;

void setup(void)
{
    // initialize serial communication
    #ifdef DEBUG_MODE
    Serial.begin(SERIAL_BAUD);
    #endif

    /* RFM69HCW INITIALIZATION ========================================= */

    #ifdef DEBUG_MODE
    Serial.println("UD RFM69HCW Initializing...");
    #endif

    // hard Reset the RFM module
    pinMode(FEATHER_RFM69_RST, OUTPUT);
    digitalWrite(FEATHER_RFM69_RST, HIGH);
    delay(100);
    digitalWrite(FEATHER_RFM69_RST, LOW);
    delay(100);

    // initialize radio
    node.initialize(FREQUENCY,NODEID,NETWORKID);
    if (IS_RFM69HCW) {
        // only for RFM69HCW & HW!
        node.setHighPower();
    }

    // power output ranges from 0 (5dBm) to 31 (20dBm)
    node.setPowerLevel(31);

    node.encrypt(EMERALDS_ENCRYPTKEY);

    #ifdef DEBUG_MODE
    pinMode(FEATHER_LED, OUTPUT);
    Serial.print("\nTransmitting at ");
    Serial.print(FREQUENCY == RF69_433MHZ ? 433 : FREQUENCY == RF69_868MHZ ? 868 : 915);
    Serial.println(" MHz");

    Serial.println("UD RFM69HCW Initialized!\n");
    #endif

    /* FUNCTION INITIALIZATION ========================================= */

    toSend = false;
    node.sleep();
}

void loop(void)
{
    /* RF COMMS ======================================================== */

    if (toSend) {
        // prepare sending data
        #ifdef DEBUG_MODE
        Serial.print("Sending \"");
        Serial.print(radiopacket);
        Serial.print("\"... ");
        #endif

        // target node Id, message as string or byte array, message length
        if (node.sendWithRetry(RECEIVER, radiopacket, strlen(radiopacket))) {
            #ifdef DEBUG_MODE
            Serial.println("OK\n");
            #endif
        }

        #ifdef DEBUG_MODE
        else {
            Serial.println("\n");
        }
        #endif

        // delay for retransmission
        delay(250);

        // reset toSend variable
        toSend = false;
    }

    // put radio in RX mode
    node.receiveDone();

    // make sure all serial data is clocked out before sleeping the MCU
    #ifdef DEBUG_MODE
    Serial.flush();
    #endif

    /* UD FUNCTION ===================================================== */

    if (IR1triggered()) {
        boolean to_right;
        while (!(to_right = IR0triggered())) {}
        if (to_right) {
            #ifdef DEBUG_MODE
            Serial.println("------->");
            #endif

            radiopacket[17] = '-';
            radiopacket[24] = '>';

            // wait for while to avoid multiple triggers
            unsigned long t = millis();
            while (millis() - t < 3000) {}
        }
        toSend = true;
        return;
    }

    if (IR0triggered()) {
        boolean to_left;
        while (!(to_left = IR1triggered())) {}
        if (to_left) {
            #ifdef DEBUG_MODE
            Serial.println("<-------");
            #endif

            radiopacket[17] = '<';
            radiopacket[24] = '-';

            // wait for while to avoid multiple triggers
            unsigned long t = millis();
            while (millis() - t < 3000) {}
        }
        toSend = true;
        return;
    }
}

boolean IR0triggered(void)
{
    // reading containers
    int ana1 = sharp1.distance();
    int ana2 = sharp2.distance();
    if (ana1 >= 0 && ana2 <= 30) {
        return true;
    }
    return false;
}

boolean IR1triggered(void)
{
    // reading containers
    int ana1 = sharp1.distance();
    int ana2 = sharp2.distance();
    if (ana1 <= 30 && ana2 >= 0) {
        return true;
    }
    return false;
}
