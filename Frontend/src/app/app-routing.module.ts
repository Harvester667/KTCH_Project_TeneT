import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { NewAppointmentComponent } from './pages/new-appointment/new-appointment.component';
import { AppointmentListComponent } from './pages/appointment-list/appointment-list.component';
import { RegisterComponent } from './register/register.component';
import { UserlistComponent } from './userlist/userlist.component';
import { LoginComponent } from './login/login.component';
import { ServicesListComponent } from './services-list/services-list.component';
import { HomeComponent } from './home/home.component';
import { PriceListComponent } from './pricelist/pricelist.component';
import { CalendarComponent } from './calendar/calendar.component';

const routes: Routes = [
  { path: '', redirectTo: 'home', pathMatch: 'full' }, // alapértelmezett útvonal átirányítása
  { path: 'home', component: HomeComponent },  // Kezdőlap
  { path: 'new', component: NewAppointmentComponent },
  { path: 'list', component: AppointmentListComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'login', component: LoginComponent },
  { path: 'superadmin', component: UserlistComponent },
  { path: 'services', component: ServicesListComponent },
  { path: 'pricelist', component: PriceListComponent },
  { path: 'calendar', component: CalendarComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { onSameUrlNavigation: 'reload' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
