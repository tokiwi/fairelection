import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResultComponent } from './result.component';

describe('ResultComponent', () => {
    let component: ResultComponent;
    let fixture: ComponentFixture<ResultComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ResultComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ResultComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
