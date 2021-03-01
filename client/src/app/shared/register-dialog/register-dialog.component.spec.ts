import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterDialogComponent } from './register-dialog.component';

describe('RegisterDialogComponent', () => {
    let component: RegisterDialogComponent;
    let fixture: ComponentFixture<RegisterDialogComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ RegisterDialogComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(RegisterDialogComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
