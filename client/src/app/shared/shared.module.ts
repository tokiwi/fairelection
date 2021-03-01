import { NgModule } from '@angular/core';
import { HelpPanelComponent } from './help-panel/help-panel.component';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { StepperComponent } from './election/stepper/stepper.component';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { MatDialogModule } from '@angular/material/dialog';
import { MatTabsModule } from '@angular/material/tabs';
import { LoginDialogComponent } from './login-dialog/login-dialog.component';
import { MatInputModule } from '@angular/material/input';
import { ReactiveFormsModule } from '@angular/forms';
import { ConfirmDialogComponent } from './confirm-dialog/confirm-dialog.component';
import { ConfirmDialogDirective } from '../core/directives/confirm-dialog.directive';
import { RouterModule } from '@angular/router';
import { RegisterDialogComponent } from './register-dialog/register-dialog.component';
import { MatButtonLoadingDirective } from '../core/directives/mat-button-loading.directive';

@NgModule({
    imports: [
        MatSidenavModule,
        MatButtonModule,
        MatIconModule,
        CommonModule,
        TranslateModule,
        MatDialogModule,
        MatTabsModule,
        MatInputModule,
        ReactiveFormsModule,
        RouterModule
    ],
    declarations: [
        HelpPanelComponent,
        ConfirmDialogComponent,
        ConfirmDialogDirective,
        MatButtonLoadingDirective,
        StepperComponent,
        LoginDialogComponent,
        RegisterDialogComponent
    ],
    exports: [
        ConfirmDialogDirective,
        MatButtonLoadingDirective,
        HelpPanelComponent,
        StepperComponent
    ],
    providers: [
    ],
    entryComponents: []
})
export class SharedModule {
}
