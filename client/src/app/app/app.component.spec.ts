import { TestBed, async } from '@angular/core/testing';
import { RouterTestingModule } from '@angular/router/testing';
import { AppComponent } from './app.component';

describe('AppComponent', () => {
    beforeEach(async(() => {
        void TestBed.configureTestingModule({
            imports: [
                RouterTestingModule
            ],
            declarations: [
                AppComponent
            ],
        }).compileComponents();
    }));

    it('should create the app', () => {
        const fixture = TestBed.createComponent(AppComponent);
        const app = fixture.componentInstance;
        void expect(app).toBeTruthy();
    });

    it('should have as title \'fair-election-client\'', () => {
        const fixture = TestBed.createComponent(AppComponent);
        const app = fixture.componentInstance;
        void expect(app.title).toEqual('fair-election-client');
    });
});
