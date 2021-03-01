import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StatisticsViewComponent } from './statistics.view';

describe('StatisticsComponent', () => {
    let component: StatisticsViewComponent;
    let fixture: ComponentFixture<StatisticsViewComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ StatisticsViewComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(StatisticsViewComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
