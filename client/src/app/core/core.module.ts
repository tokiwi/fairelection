import { ErrorHandler, NgModule } from '@angular/core';
import { I18nService } from './services/i18n.service';
import { LoadingService } from './services/loading.service';
import { AuthService } from './services/auth.service';
import { ApiService } from './services/api.service';
import { ElectionService } from './services/election.service';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpRequestInterceptor } from './interceptors/http.request.interceptor';
import { HttpAuthorizationInterceptor } from './interceptors/http.authorization.interceptor';
import { HttpLanguageInterceptor } from './interceptors/http.language.interceptor';
import { HttpContentTypeInterceptor } from './interceptors/http.content-type.interceptor';
import { AppErrorHandler } from './handlers/app-error.handler';
import { NotificationService } from './services/notification.service';
import { ElectionResolver } from './resolvers/election.resolver';
import { CriteriaService } from './services/criteria.service';
import { CriteriasResolver } from './resolvers/criterias.resolver';
import { ElectionCriteriaService } from './services/election-criteria.service';
import { UserService } from './services/user.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { HttpErrorInterceptor } from './interceptors/http-error.interceptor';
import { AssignmentResourceService } from './services/assignment-resource.service';
import { CandidateResourceService } from './services/candidate-resource.service';
import { StatisticResourceService } from './services/statistic-resource.service';
import { CandidateService } from './services/candidate.service';
import { CandidatesResolver } from './resolvers/candidates.resolver';
import { ResultResourceService } from './services/result-resource.service';
import { SolverResourceService } from './services/solver-resource.service';
import { AuthGuard } from './guards/auth.guard';
import { ConfirmDialogService } from './services/confirm-dialog.service';
import { TitleService } from './services/title.service';
import { ConfirmDialogFactory } from './factory/confirm-dialog.factory';

@NgModule({
    imports: [
    ],
    declarations: [
    ],
    exports: [
    ],
    providers: [
        I18nService,
        LoadingService,
        AuthService,
        AuthGuard,
        ApiService,
        ElectionService,
        CriteriaService,
        NotificationService,
        ElectionResolver,
        CriteriasResolver,
        CandidatesResolver,
        ElectionCriteriaService,
        AssignmentResourceService,
        CandidateResourceService,
        StatisticResourceService,
        ResultResourceService,
        ConfirmDialogService,
        ConfirmDialogFactory,
        SolverResourceService,
        TitleService,
        CandidateService,
        UserService,
        AppErrorHandler,
        MatSnackBar,
        {provide: HTTP_INTERCEPTORS, useClass: HttpRequestInterceptor, multi: true},
        {provide: HTTP_INTERCEPTORS, useClass: HttpErrorInterceptor, multi: true},
        {provide: HTTP_INTERCEPTORS, useClass: HttpAuthorizationInterceptor, multi: true},
        {provide: HTTP_INTERCEPTORS, useClass: HttpLanguageInterceptor, multi: true},
        {provide: HTTP_INTERCEPTORS, useClass: HttpContentTypeInterceptor, multi: true},
        {provide: ErrorHandler, useClass: AppErrorHandler},
    ],
    entryComponents: []
})
export class CoreModule {
}
