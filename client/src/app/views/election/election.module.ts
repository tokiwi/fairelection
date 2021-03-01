import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ElectionRoutingModule } from './election-routing.module';
import { MatCardModule } from '@angular/material/card';
import { MatInputModule } from '@angular/material/input';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '../../shared/shared.module';
import { CreateElectionComponent } from './create-election/create-election.component';
import { ChooseCriteriaComponent } from './choose-criteria/choose-criteria.component';
import { CriteriaAssignmentComponent } from './criteria-assignment/criteria-assignment.component';
import { CandidateComponent } from './candidate/candidate.component';
import { VoteComponent } from './vote/vote.component';
import { TranslateModule } from '@ngx-translate/core';
import { MatButtonModule } from '@angular/material/button';
import { ComponentsModule } from '../../components/components.module';
import { MatIconModule } from '@angular/material/icon';
import { MatSliderModule } from '@angular/material/slider';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatRadioModule } from '@angular/material/radio';
import { MatTableModule } from '@angular/material/table';
import { MatTooltipModule } from '@angular/material/tooltip';
import { StatisticsViewComponent } from './statistics/statistics.view';
import { SolveComponent } from './solve/solve.component';
import { ResultComponent } from './result/result.component';
import { ElectionsComponent } from './elections/elections.component';
import { MatPaginatorModule } from '@angular/material/paginator';
import { DragScrollModule } from 'ngx-drag-scroll';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';



@NgModule({
    declarations: [
        ElectionsComponent,
        CreateElectionComponent,
        ChooseCriteriaComponent,
        CriteriaAssignmentComponent,
        CandidateComponent,
        VoteComponent,
        StatisticsViewComponent,
        SolveComponent,
        ResultComponent,
    ],
    imports: [
        ComponentsModule,
        ElectionRoutingModule,
        FormsModule,
        ReactiveFormsModule,
        MatInputModule,
        MatRadioModule,
        MatSliderModule,
        MatTableModule,
        MatCardModule,
        TranslateModule,
        SharedModule,
        CommonModule,
        MatButtonModule,
        MatIconModule,
        MatSidenavModule,
        MatTooltipModule,
        MatPaginatorModule,
        DragScrollModule,
        MatProgressSpinnerModule
    ]
})
export class ElectionModule { }
