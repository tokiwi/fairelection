import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResetPasswordRequestComponent } from './reset-password-request.component';

describe('ResetPasswordRequestComponent', () => {
    let component: ResetPasswordRequestComponent;
    let fixture: ComponentFixture<ResetPasswordRequestComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ResetPasswordRequestComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ResetPasswordRequestComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
