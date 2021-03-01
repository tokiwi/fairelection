import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddCriteriaDialogComponent } from './add-criteria-dialog.component';

describe('AddCriteriaDialogComponent', () => {
    let component: AddCriteriaDialogComponent;
    let fixture: ComponentFixture<AddCriteriaDialogComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ AddCriteriaDialogComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AddCriteriaDialogComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
