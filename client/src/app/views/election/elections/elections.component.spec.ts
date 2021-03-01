import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ElectionsComponent } from './elections.component';

describe('ElectionsComponent', () => {
    let component: ElectionsComponent;
    let fixture: ComponentFixture<ElectionsComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ ElectionsComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ElectionsComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
