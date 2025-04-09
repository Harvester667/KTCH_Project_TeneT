import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';
import { tap } from 'rxjs/operators';
import { throwError } from 'rxjs';

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
        const user = response.data.user;
        const token = response.data?.token;
  
        if (user && token) {
          localStorage.setItem('token', token);
          localStorage.setItem('user', JSON.stringify(user));
          this.userSubject.next(user); // Ez kell, hogy frissítse az AppComponentet
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
    const headers = this.getAuthHeaders();
    return this.http.get<any[]>(`${this.apiUrl}/getusers`, { headers });
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

  logout(): void {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.userSubject.next(null);
  }

  isLoggedIn(): boolean {
    return this.userSubject.getValue() !== null;
  }

  bookAppointment(bookingData: any) {
    return this.http.post(`${this.apiUrl}/bookings`, bookingData);
  }

  getServices(): Observable<any> {
    return this.http.get(`${this.apiUrl}/services`);
  }

  addService(serviceData: { service: string, price: number }): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(`${this.apiUrl}/addservice`, serviceData, { headers });
  }

  updateService(id: number, service: { service: string, price: number }): Observable<any> {
    const updatedService = {
      service: service.service,
      price: service.price
    };
    const headers = this.getAuthHeaders(); // Ha szükséges, adj hozzá authentikációs fejléceket
    return this.http.put(`${this.apiUrl}/updateservice/${id}`, updatedService, { headers });
  }
  

  deleteService(serviceId: number): Observable<any> {
    const headers = this.getAuthHeaders(true);
    return this.http.delete(`${this.apiUrl}/deleteservice/${serviceId}`, { headers });
  }

  getEmployees(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.get<any>(`${this.apiUrl}/employees`, { headers });
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
    return this.http.get<any>(`${this.apiUrl}/getbookings`, { headers: this.getAuthHeaders() });  // Az API végpont pontos megadása
  }

}

