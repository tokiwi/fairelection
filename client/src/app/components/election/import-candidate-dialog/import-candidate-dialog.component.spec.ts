import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImportCandidateDialogComponent } from './import-candidate-dialog.component';

describe('ImportCandidateDialogComponent', () => {
    let component: ImportCandidateDialogComponent;
    let fixture: ComponentFixture<ImportCandidateDialogComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ImportCandidateDialogComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ImportCandidateDialogComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
