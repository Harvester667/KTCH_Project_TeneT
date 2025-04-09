import { Component, OnInit } from '@angular/core';
import { AuthService } from './auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  user: any;

  constructor(public authService: AuthService) {}

  ngOnInit() {
    this.authService.user$.subscribe(user => {
      console.log('AppComponent user változás:', user);
      this.user = user;
    });
  }

  logout() {
    this.authService.logout();
  }
}
