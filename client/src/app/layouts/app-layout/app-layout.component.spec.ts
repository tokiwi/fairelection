import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppLayoutComponent } from './app-layout.component';

describe('AppLayoutComponent', () => {
    let component: AppLayoutComponent;
    let fixture: ComponentFixture<AppLayoutComponent>;

    beforeEach(async(() => {
        void void TestBed.configureTestingModule({
            declarations: [ AppLayoutComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AppLayoutComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void void expect(component).toBeTruthy();
    });
});
