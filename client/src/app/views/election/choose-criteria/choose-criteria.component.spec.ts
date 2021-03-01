import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChooseCriteriaComponent } from './choose-criteria.component';

describe('ChooseCriteriaComponent', () => {
    let component: ChooseCriteriaComponent;
    let fixture: ComponentFixture<ChooseCriteriaComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ChooseCriteriaComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ChooseCriteriaComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
