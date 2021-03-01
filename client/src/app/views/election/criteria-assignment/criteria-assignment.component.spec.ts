import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CriteriaAssignmentComponent } from './criteria-assignment.component';

describe('CriteriaAssignmentComponent', () => {
    let component: CriteriaAssignmentComponent;
    let fixture: ComponentFixture<CriteriaAssignmentComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ CriteriaAssignmentComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(CriteriaAssignmentComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
