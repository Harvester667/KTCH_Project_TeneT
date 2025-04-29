import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';
import { tap } from 'rxjs/operators';
import { throwError } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost:8000/api';
  private userSubject = new BehaviorSubject<any>(this.getUserFromLocalStorage());
  user$ = this.userSubject.asObservable();

  constructor(private http: HttpClient) {
    this.loadUserFromStorage();
  }

  private getUserFromLocalStorage(): any {
    const userJson = localStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
  }

  private loadUserFromStorage() {
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('user');
    if (token && user) {
      this.userSubject.next(JSON.parse(user));
    }
  }

  register(registrationData: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/register`, registrationData);
  }

  login(credentials: { email: string; password: string }): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, credentials).pipe(
      tap((response: any) => {
        // Itt ellenőrizni kell, hogy a válasz sikeres-e
        if (response.success) {
          const user = response.data.user;
          const token = response.data?.token;
  
          if (user && token) {
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            this.userSubject.next(user);
          }
        }
      })
    );
  }
  

  getUser(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.get<any>(`${this.apiUrl}/user`, { headers }).pipe(
      tap(user => {
        this.userSubject.next(user);
        localStorage.setItem('user', JSON.stringify(user)); // Frissítés localStorage-ban
      })
    );
  }

  getUsers(): Observable<any[]> {
    const storedUser = localStorage.getItem('user');
    const user = storedUser ? JSON.parse(storedUser) : null;
  
    let headers = new HttpHeaders();
  
    if (user && (user.admin === 1 || user.admin === 2)) {
      const token = localStorage.getItem('token');
      if (token) {
        headers = headers.set('Authorization', `Bearer ${token}`);
      }
    }
    
    return this.http.get<any>(`${this.apiUrl}/getusers`, { headers }).pipe(
      map(response => {
        console.log('Felhasználók válasza:', response);
        return response.data || [];
      })
    );
  }
  setAdmin(id: number): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.put(`${this.apiUrl}/admin/${id}`, {}, { headers });
  }

  demotivate(id: number): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.put(`${this.apiUrl}/polymorph/${id}`, {}, { headers });
  }

  addEmployee(userId: number): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(`${this.apiUrl}/add-employee/${userId}`, {}, { headers });
  }

  employee(id: number) {
    const token = localStorage.getItem('token'); // vagy ott, ahol tárolod
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.put<any>(`${this.apiUrl}/employee/${id}`, {}, { headers });
  }
  
  customer(id: number) {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.put<any>(`${this.apiUrl}/customer/${id}`, {}, { headers });
  }

  removeEmployee(userId: number): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(`${this.apiUrl}/remove-employee/${userId}`, {}, { headers });
  }

  logout(): void {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.userSubject.next(null);
  }

  isLoggedIn(): boolean {
    return this.userSubject.getValue() !== null;
  }

  bookAppointment(bookingData: any) {
    const token = localStorage.getItem('token');
  
    if (!token) {
      console.error('Nincs token!');
      // Ilyenkor is visszaadunk egy hibás Observable-t, hogy ne legyen típushiba
      return new Observable(observer => {
        observer.error('Nincs token a localStorage-ban.');
      });
    }
  
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
  
    return this.http.post('http://localhost:8000/api/addbooking', bookingData, { headers });
  }

  forceBookAppointment(bookingData: any): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(`${this.apiUrl}/forcebooking`, bookingData, { headers });
  }
  
   

  getServices() {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<any>(`${this.apiUrl}/services`, { headers });
  }
  
  addService(service: any) {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post<any>(`${this.apiUrl}/addservice`, service, { headers });
  }
  
  updateService(id: number, service: any) {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.patch<any>(`${this.apiUrl}/updateservice/${id}`, service, { headers });
  }
  
  deleteService(id: number) {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.delete<any>(`${this.apiUrl}/delservice/${id}`, { headers });
  }

  getEmployees(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.get(`${this.apiUrl}/employees`, { headers });
  }

  getBookedAppointments(employeeId: number, date: string): Observable<string[]> {
    const headers = this.getAuthHeaders();
    return this.http.get<string[]>(`${this.apiUrl}/booked-appointments/${employeeId}/${date}`, { headers });
  }

  // Segédfüggvény az auth fejléchez
  private getAuthHeaders(includeJson: boolean = false): HttpHeaders {
    const token = localStorage.getItem('token') || '';
    let headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    if (includeJson) {
      headers = headers.set('Content-Type', 'application/json');
    }

    return headers;
  }

  getBookings(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/bookings`, { headers: this.getAuthHeaders() });  // Az API végpont pontos megadása
  }

  getCalendarBookedAppointments(employeeId: number, date: string): Observable<any[]> {
    const headers = this.getAuthHeaders();
    return this.http.get<any[]>(`${this.apiUrl}/calendar-booked-appointments/${employeeId}/${date}`, { headers });
  }

  // cancelBooking(bookingId: number): Observable<any> {
  //   return this.http.delete<any>(`${this.apiUrl}/cancel-booking`, { body: { booking_id: bookingId } });
  // }

  delete(endpoint: string): Observable<any> {
    const url = `${this.apiUrl}/${endpoint}`;
    
    // Ha szükség van hitelesítésre, akkor biztosítjuk a token-t
    const headers = {
      'Authorization': `Bearer ${localStorage.getItem('token')}`  // Példa, ha token van tárolva
    };
  
    return this.http.delete(url, { headers });
  }
}
