import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CandidateComponent } from './candidate.component';

describe('CandidateComponent', () => {
    let component: CandidateComponent;
    let fixture: ComponentFixture<CandidateComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ CandidateComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(CandidateComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
