import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewAppointmentComponent } from './new-appointment.component';

describe('NewAppointmentComponent', () => {
  let component: NewAppointmentComponent;
  let fixture: ComponentFixture<NewAppointmentComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [NewAppointmentComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewAppointmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
