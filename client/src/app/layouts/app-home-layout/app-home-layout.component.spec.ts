import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppHomeLayoutComponent } from './app-home-layout.component';

describe('AppHomeLayoutComponent', () => {
    let component: AppHomeLayoutComponent;
    let fixture: ComponentFixture<AppHomeLayoutComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ AppHomeLayoutComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AppHomeLayoutComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
