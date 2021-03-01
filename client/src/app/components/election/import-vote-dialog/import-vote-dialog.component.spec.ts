import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImportVoteDialogComponent } from './import-vote-dialog.component';

describe('ImportVoteDialogComponent', () => {
    let component: ImportVoteDialogComponent;
    let fixture: ComponentFixture<ImportVoteDialogComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ImportVoteDialogComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ImportVoteDialogComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
