import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppLayoutComponent } from './layouts/app-layout/app-layout.component';
import { HomeComponent } from './views/home/home.component';
import { RegisterComponent } from './views/register/register.component';
import { LoginComponent } from './views/login/login.component';
import { AppHomeLayoutComponent } from './layouts/app-home-layout/app-home-layout.component';
import { FaqComponent } from './views/faq/faq.component';
import { ResetPasswordRequestComponent } from './views/reset-password-request/reset-password-request.component';
import { ResetPasswordComponent } from './views/reset-password/reset-password.component';


const routes: Routes = [
    {
        path: '',
        component: AppHomeLayoutComponent,
        children: [
            {
                path: '',
                component: HomeComponent,
                pathMatch: 'full'
            },
        ]
    }, {
        path: '',
        component: AppLayoutComponent,
        children: [
            {
                path: 'register',
                component: RegisterComponent,
                pathMatch: 'full'
            },
            {
                path: 'login',
                component: LoginComponent,
                pathMatch: 'full'
            },
            {
                path: 'reset-password',
                component: ResetPasswordRequestComponent,
                pathMatch: 'full'
            },
            {
                path: 'reset-password/:token',
                component: ResetPasswordComponent,
                pathMatch: 'full'
            },
            {
                path: 'faq',
                component: FaqComponent,
                pathMatch: 'full'
            }
        ]
    }, {
        path: 'elections',
        loadChildren: () => import('./views/election/election.module').then((m) => m.ElectionModule)
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes, {
        onSameUrlNavigation: 'reload',
        scrollPositionRestoration: 'enabled'
    })],
    exports: [RouterModule]
})
export class AppRoutingModule { }
