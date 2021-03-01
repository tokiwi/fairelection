import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CreateElectionComponent } from './create-election/create-election.component';
import { AppLayoutComponent } from '../../layouts/app-layout/app-layout.component';
import { ChooseCriteriaComponent } from './choose-criteria/choose-criteria.component';
import { ElectionResolver } from '../../core/resolvers/election.resolver';
import { CriteriasResolver } from '../../core/resolvers/criterias.resolver';
import { CriteriaAssignmentComponent } from './criteria-assignment/criteria-assignment.component';
import { CandidateComponent } from './candidate/candidate.component';
import { StatisticsViewComponent } from './statistics/statistics.view';
import { VoteComponent } from './vote/vote.component';
import { CandidatesResolver } from '../../core/resolvers/candidates.resolver';
import { SolveComponent } from './solve/solve.component';
import { ResultComponent } from './result/result.component';
import { ElectionsComponent } from './elections/elections.component';
import { AuthGuard } from '../../core/guards/auth.guard';
import { DirtyFormGuard } from '../../core/guards/dirty-form-guard.service';

const routes: Routes = [{
    path: '',
    component: AppLayoutComponent,
    children: [
        {
            path: '',
            component: ElectionsComponent,
            pathMatch: 'full',
            canActivate: [
                AuthGuard,
            ]
        }, {
            path: 'create',
            component: CreateElectionComponent,
            pathMatch: 'full'
        }, {
            path: ':electionId/criterias',
            component: ChooseCriteriaComponent,
            resolve: {
                election: ElectionResolver,
                criterias: CriteriasResolver,
            }
        }, {
            path: ':electionId/assignments',
            component: CriteriaAssignmentComponent,
            canDeactivate: [
                DirtyFormGuard,
            ],
            resolve: {
                election: ElectionResolver,
            }
        }, {
            path: ':electionId/candidates',
            component: CandidateComponent,
            runGuardsAndResolvers: 'always',
            canDeactivate: [
                DirtyFormGuard,
            ],
            resolve: {
                election: ElectionResolver,
            }
        }, {
            path: ':electionId/statistics',
            component: StatisticsViewComponent,
            resolve: {
                election: ElectionResolver,
            },
            canActivate: [
                AuthGuard,
            ]
        }, {
            path: ':electionId/votes',
            component: VoteComponent,
            canDeactivate: [
                DirtyFormGuard,
            ],
            resolve: {
                election: ElectionResolver,
                candidates: CandidatesResolver,
            },
            canActivate: [
                AuthGuard
            ]
        }, {
            path: ':electionId/solve',
            component: SolveComponent,
            resolve: {
                election: ElectionResolver,
            },
            canActivate: [
                AuthGuard
            ]
        }, {
            path: ':electionId/results',
            component: ResultComponent,
            resolve: {
                election: ElectionResolver,
            },
            canActivate: [
                AuthGuard,
            ]
        }
    ]
}];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class ElectionRoutingModule {}
