import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CriteriaPercentAssignmentComponent } from './criteria-percent-assignment.component';

describe('CriteriaPercentAssignmentComponent', () => {
    let component: CriteriaPercentAssignmentComponent;
    let fixture: ComponentFixture<CriteriaPercentAssignmentComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ CriteriaPercentAssignmentComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(CriteriaPercentAssignmentComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
