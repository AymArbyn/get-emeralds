/* ======================================================================
 *
 * Green Electronic Technology: Efficient Management of Energy through
 * Responsive and Attentive Live-Data Sourcing (GET:EMERALDS)
 *
 * Filename: server.ino
 *
 * Written by:
 *   - Arbyn Acosta <arbyn.acosta@ieee.org>
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

#define DEBUG_MODE

int ctr;
int rnd[6];

void setup(void)
{
	Serial.begin(9600);
	randomSeed(analogRead(A0));
	for (int i = 0; i < 6; ++i)
	{
		rnd[i] = random(40, 100);
	}
	ctr = 0;
}

void loop(void)
{
	ctr++;
	if (ctr > 60) {
		for (int i = 0; i < 6; ++i)
		{
			rnd[i] = random(40, 100);
		}
		ctr = 0;
	}

	// radio packet
	String radiopacket_build = "FROM OUTLET " +
		String(rnd[0] / 100.0, 2) + " " + String(rnd[1] / 100.0, 2) + " " +
		String(rnd[2] / 100.0, 2) + " " + String(rnd[3] / 100.0, 2) + " " +
		String((rnd[4] * 2) / 100.0, 2) + " " + String((rnd[5] * 2) / 100.0, 2) + " " +
		0 + " " + 0 + " " + 0 + " " +
		0 + " " + 0 + " " + 0;

	// print message received to serial
	#ifdef DEBUG_MODE
	printReceived(OUTLET_NET_ID, -21, radiopacket_build);
	Serial.println(" - ACK sent");
	#endif

	delay(1000);
}
