import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { FullCalendarModule } from '@fullcalendar/angular'; 
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid'; // A heti és óránkénti nézetekhez
import interactionPlugin from '@fullcalendar/interaction'; // Az események kattintásához

declare var bootstrap: any; // Bootstrap modál deklaráció

@Component({
  selector: 'app-calendar',
  templateUrl: './calendar.component.html',
  styleUrls: ['./calendar.component.css']
})
export class CalendarComponent implements OnInit {
  // Események típusának meghatározása
  calendarOptions: {
    initialView: string;
    plugins: any[];
    events: { title: string; start: string }[]; // Explicit típust adunk az events tömbnek
    eventClick: (event: any) => void;
    headerToolbar: {
      left: string;
      center: string;
      right: string;
    };
    views: {
      timeGridWeek: {
        slotDuration: string;
      };
    };
  } = {
    initialView: 'timeGridWeek', // Alapértelmezett nézet heti
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin], // Hozzáadjuk az összes szükséges plugint
    events: [], // A naptár eseményei (most már típus is van)
    eventClick: this.handleEventClick.bind(this), // Event kattintás kezelés
    headerToolbar: {
      left: 'prev,next today', // Naptár navigáció
      center: 'title', // Cím középre
      right: 'dayGridMonth,timeGridWeek,timeGridDay' // Hónap, hét és napi nézetek
    },
    views: {
      timeGridWeek: {
        slotDuration: '01:00:00', // Egy óra blokkok
      },
    },
  };

  constructor(private authService: AuthService) {}

  ngOnInit() {
    this.loadBookedAppointments(1, '2025-04-12');  // Példa employeeId és dátum
  }

  loadBookedAppointments(employeeId: number, date: string) {
    this.authService.getCalendarBookedAppointments(employeeId, date).subscribe(
      (appointments) => {
        // A válasz a foglalások listája, itt minden foglalás egy időpontot tartalmaz
        const events = appointments.map((appointment) => ({
          title: `Booked: ${appointment.appointment_time}`,  // Esemény címének beállítása
          start: `${date}T${appointment.appointment_time}:00`,  // Az időpont és dátum összekapcsolása
        }));
        this.calendarOptions.events = events;  // Az események frissítése
      },
      (error) => {
        console.error('Hiba a foglalások betöltésekor:', error);  // Hiba esetén üzenet
      }
    );
  }

  handleEventClick(event: any) {
    console.log('Eseményre kattintottál:', event.event.title);  // Kattintott esemény neve
    // Események kezelése, például modal megnyitása
  }
}
