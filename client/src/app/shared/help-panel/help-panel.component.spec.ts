import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HelpPanelComponent } from './help-panel.component';

describe('HelpPanelComponent', () => {
    let component: HelpPanelComponent;
    let fixture: ComponentFixture<HelpPanelComponent>;

    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            declarations: [ HelpPanelComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(HelpPanelComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        void expect(component).toBeTruthy();
    });
});
