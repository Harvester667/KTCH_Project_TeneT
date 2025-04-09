import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MasterService {
  private apiEndPoint: string = "http://localhost:8000/api/";

  constructor(private http: HttpClient) {}

  // Új foglalás létrehozása
  createNew(obj: any): Observable<any> {
    return this.http.post(this.apiEndPoint + "addbooking", obj);
  }

  // Összes foglalás lekérése
  getAllAppointments(): Observable<any> {
    return this.http.get(this.apiEndPoint + "bookings");
  }

  // Egy adott foglalás lekérése
  getOneAppointment(id: number): Observable<any> {
    return this.http.get(`${this.apiEndPoint}onebooking/${id}`);
  }

  // Foglalás frissítése
  updateAppointment(id: number, obj: any): Observable<any> {
    return this.http.put(`${this.apiEndPoint}updatebooking/${id}`, obj);
  }

  // Foglalás törlése
  deleteAppointment(id: number): Observable<any> {
    return this.http.delete(`${this.apiEndPoint}deletebooking/${id}`);
  }

  // Legutóbbi foglalás azonosító lekérése
  getBookingId(): Observable<any> {
    return this.http.get(this.apiEndPoint + "getbookingid");
  }
}
