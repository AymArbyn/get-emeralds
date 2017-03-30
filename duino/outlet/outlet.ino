/* ======================================================================
 *
 * Green Electronic Technology: Efficient Management of Energy through
 * Responsive and Attentive Live-Data Sourcing (GET:EMERALDS)
 *
 * Filename: outlet.ino
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

// debugging mode
#define DEBUG_MODE

#define TRASMIT_PERIOD 500

// networking ids
#define NETWORKID  EMERALDS_NET_ID
#define NODEID     OUTLET_NET_ID
#define RECEIVER   MARC_NET_ID

// create radio instance
RFM69 node = RFM69(FEATHER_RFM69_CS, FEATHER_RFM69_IRQ, IS_RFM69HCW, FEATHER_RFM69_IRQN);

// function prototypes
float DC1(void);
float DC2(void);
float DC3(void);
float DC4(void);
float AC1(void);
float AC2(void);

// function variables
float DC1Voltage = 0, DC2Voltage = 0, DC3Voltage = 0, DC4Voltage = 0;
float DC1Amps, DC2Amps, DC3Amps, DC4Amps;
float AC1Amps, AC2Amps;
String radiopacket_build;
static char radiopacket[DATA_MAX];
static boolean toSend;
static boolean states[6];
unsigned long t;

void setup(void)
{
    // initialize serial communication
    #ifdef DEBUG_MODE
    Serial.begin(SERIAL_BAUD);
    #endif

    /* RFM69HCW INITIALIZATION ========================================= */

    Serial.println("Outlet RFM69HCW Initializing...");

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

    Serial.println("Outlet RFM69HCW Initialized!\n");
    #endif

    /* FUNCTION INITIALIZATION ========================================= */

    pinMode(DC1R, OUTPUT);
    pinMode(DC2R, OUTPUT);
    pinMode(DC3R, OUTPUT);
    pinMode(DC4R, OUTPUT);
    pinMode(AC1R, OUTPUT);
    pinMode(AC2R, OUTPUT);
    for (uint8_t i = 0; i < 6; i++) {
        states[i] = LOW;
    }

    // timer for data transmission
    t = millis();
}

void loop(void)
{
    /* RF COMMS ======================================================== */

    // check if something was received (could be an interrupt from the radio)
    if (node.receiveDone()) {
        // print message received to serial
        #ifdef DEBUG_MODE
        Serial.print('[');
        Serial.print(node.SENDERID);
        Serial.print("] [");
        Serial.print((char*) node.DATA);
        Serial.print("]   [RX_RSSI:");
        Serial.print(node.RSSI);
        Serial.print("]");
        #endif

        // check if sender wanted an ACK
        if (node.ACKRequested()) {
            node.sendACK();
            #ifdef DEBUG_MODE
            Serial.println(" - ACK sent");
            #endif
        }

        #ifdef DEBUG_MODE
        else {
            Serial.println();
        }
        #endif

        if (strstr((char*) node.DATA, "TO OUTLET RELAY 1")) {
            states[0] = !states[0];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 1 state...");
            #endif
        }
        else if (strstr((char*) node.DATA, "TO OUTLET RELAY 2")) {
            states[1] = !states[1];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 2 state...");
            #endif
        }
        else if (strstr((char*) node.DATA, "TO OUTLET RELAY 3")) {
            states[2] = !states[2];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 3 state...");
            #endif
        }
        else if (strstr((char*) node.DATA, "TO OUTLET RELAY 4")) {
            states[3] = !states[3];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 4 state...");
            #endif
        }
        else if (strstr((char*) node.DATA, "TO OUTLET RELAY 5")) {
            states[4] = !states[4];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 5 state...");
            #endif
        }
        else if (strstr((char*) node.DATA, "TO OUTLET RELAY 6")) {
            states[5] = !states[5];
            #ifdef DEBUG_MODE
                Serial.println("Changing Relay 6 state...");
            #endif
        }

        digitalWrite(DC1R, states[0]);
        digitalWrite(DC2R, states[1]);
        digitalWrite(DC3R, states[2]);
        digitalWrite(DC4R, states[3]);
        digitalWrite(AC1R, states[4]);
        digitalWrite(AC2R, states[5]);

        // delay for reception
        delay(250);
    }

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

        // reset toSend variable
        toSend = false;
    }

    // put radio in RX mode
    node.receiveDone();

    // make sure all serial data is clocked out before sleeping the MCU
    Serial.flush();

    /* OUTLET FUNCTION ================================================= */

    if (millis() - t >= TRASMIT_PERIOD) {
        #ifdef DEBUG_MODE
        Serial.print("DC1Amps = ");
        int DC1Value = DC1();
        Serial.print(DC1Value, 2);
        Serial.print("\t DC2mps = ");
        Serial.print(DC2(), 2);
        Serial.print("\t DC3Amps = ");
        Serial.print(DC3(), 2);
        Serial.print("\t DC4Amps = ");
        Serial.print(DC4(), 2);
        Serial.print("\t AC1Amps = ");
        Serial.print(AC1(), 2);
        Serial.print("\t AC2Amps = ");
        Serial.println(AC2(), 2);
        #else
        DC1();
        DC2();
        DC3();
        DC4();
        AC1();
        AC2();
        #endif

        // convert readings into string array
        radiopacket_build = "FROM OUTLET " +
                            String(DC1Amps) + " " + String(DC2Amps) + " " +
                            String(DC3Amps) + " " + String(DC4Amps) + " " +
                            String(AC1Amps) + " " + String(AC2Amps) + " " +
                            states[0] + " " + states[1] + " " + states[2] + " " +
                            states[3] + " " + states[4] + " " + states[5];
        radiopacket_build.toCharArray(radiopacket, DATA_MAX);

        toSend = true;
        t = millis();
    }
    else {
        return;
    }
}

float DC1(void)
{
    float DC1Reading = analogRead(A0);
    DC1Voltage = (((DC1Reading) / 1023.0) * 5.0);
    DC1Amps = abs (2 - ((DC1Voltage - 2.5) / 0.66));
    return DC1Amps;
}

float DC2(void)
{
    float DC2Reading = analogRead(A1);
    DC2Voltage = (DC2Reading / 1023.0) * 5.0;
    DC2Amps =  abs (2 - ((DC2Voltage - 2.5) / 0.66));
    return DC2Amps;
}

float DC3(void)
{
    float DC3Reading = analogRead(A2);
    DC3Voltage = (DC3Reading / 1023.0) * 5.0;
    DC3Amps = abs (2 - ((DC3Voltage - 2.5) / 0.66));
    return DC3Amps;
}

float DC4(void)
{
    float DC4Reading = analogRead(A3);
    DC4Voltage = (((DC4Reading) / 1023.0) * 5.0);
    DC4Amps =  abs (2 - ((DC4Voltage - 2.5) / 0.66));
    return DC4Amps;
}

float AC1(void)
{
    int maxValue = 0;
    int minValue = 1024;
    uint32_t start_time = millis();

    while ((millis() - start_time) < 1000) {
        float AC1Reading = analogRead(A4);
        if (AC1Reading > maxValue) {
            maxValue = AC1Reading;
        }
        if (AC1Reading < minValue) {
            minValue = AC1Reading;
        }
    }

    AC1Amps = ((maxValue - minValue) * 5.0) / 1024.0;
    AC1Amps = AC1Amps - 0.09;

    if (AC1Amps <= 0.03) {
        AC1Amps = 0;
    }

    return AC1Amps;
}

float AC2(void)
{
    int maxValue = 0;
    int minValue = 1024;
    uint32_t start_time = millis();

    while ((millis() - start_time) < 1000) {
        float AC2Reading = analogRead(A5);
        if (AC2Reading > maxValue) {
            maxValue = AC2Reading;
        }
        if (AC2Reading < minValue) {
            minValue = AC2Reading;
        }
    }

    AC2Amps = ((maxValue - minValue) * 5.0) / 1024.0;
    AC2Amps = AC2Amps - 0.09;

    if (AC2Amps <= 0.03) {
        AC2Amps = 0;
    }

    return AC2Amps;
}
