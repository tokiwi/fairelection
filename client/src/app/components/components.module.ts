import { NgModule } from '@angular/core';
import { AddCriteriaDialogComponent } from './election/add-criteria-dialog/add-criteria-dialog.component';
import { MatDialogModule } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import { TranslateModule } from '@ngx-translate/core';
import { ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { CommonModule } from '@angular/common';
import { MatOptionModule } from '@angular/material/core';
import { MatSelectModule } from '@angular/material/select';
import { MatIconModule } from '@angular/material/icon';
import { CriteriaPercentAssignmentComponent } from './election/criteria-percent-assignment/criteria-percent-assignment.component';
import { MatCardModule } from '@angular/material/card';
import { MatSliderModule } from '@angular/material/slider';
import { StatisticComponent } from './election/statistic/statistic.component';
import { RouterModule } from '@angular/router';
import { ResultComponent } from './election/result/result.component';
import { MatTooltipModule } from '@angular/material/tooltip';
import { SharedModule } from '../shared/shared.module';
import { DragScrollModule } from 'ngx-drag-scroll';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { ImportCandidateDialogComponent } from './election/import-candidate-dialog/import-candidate-dialog.component';
import { ImportVoteDialogComponent } from './election/import-vote-dialog/import-vote-dialog.component';
import { MatRadioModule } from '@angular/material/radio';

@NgModule({
    imports: [
        MatDialogModule,
        MatButtonModule,
        TranslateModule,
        ReactiveFormsModule,
        MatFormFieldModule,
        MatInputModule,
        MatSelectModule,
        CommonModule,
        MatOptionModule,
        MatIconModule,
        MatCardModule,
        MatSliderModule,
        RouterModule,
        MatTooltipModule,
        SharedModule,
        DragScrollModule,
        MatProgressSpinnerModule,
        MatRadioModule
    ],
    declarations: [
        AddCriteriaDialogComponent,
        CriteriaPercentAssignmentComponent,
        StatisticComponent,
        ResultComponent,
        ImportCandidateDialogComponent,
        ImportVoteDialogComponent,
    ],
    exports: [
        AddCriteriaDialogComponent,
        TranslateModule,
        CriteriaPercentAssignmentComponent,
        StatisticComponent,
        ResultComponent,
    ],
    providers: [
    ],
    entryComponents: []
})
export class ComponentsModule {
}
