import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SolveComponent } from './solve.component';

describe('SolveComponent', () => {
    let component: SolveComponent;
    let fixture: ComponentFixture<SolveComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ SolveComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(SolveComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
