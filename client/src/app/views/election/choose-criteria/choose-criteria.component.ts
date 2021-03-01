import { Component, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Election } from '../../../core/models/election.model';
import { Criteria } from '../../../core/models/criteria.model';
import { AbstractControl, FormArray, FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { ElectionCriteriaService } from '../../../core/services/election-criteria.service';
import { ElectionCriteria } from '../../../core/models/election-criteria.model';
import { AuthService } from '../../../core/services/auth.service';
import { MatDialog } from '@angular/material/dialog';
import { AddCriteriaDialogComponent } from '../../../components/election/add-criteria-dialog/add-criteria-dialog.component';
import { CriteriaService } from '../../../core/services/criteria.service';
import { FormUtil } from '../../../core/utils/form.util';
import { CountValidator } from '../../../core/validators/count.validator';
import { IriUtil } from '../../../core/utils/iri.util';
import { TitleService } from '../../../core/services/title.service';
import { finalize, map, take, takeUntil, tap } from 'rxjs/operators';
import { LoginDialogComponent } from '../../../shared/login-dialog/login-dialog.component';
import { Subject } from 'rxjs';

@Component({
    selector: 'app-choose-criteria',
    templateUrl: './choose-criteria.component.html',
    styleUrls: ['./choose-criteria.component.scss']
})
export class ChooseCriteriaComponent implements OnDestroy {
    public election: Election;
    public criterias: Criteria[];

    public disable = false;

    public form = this.fb.group({
        criterias: this.fb.array([], [CountValidator(1)])
    });

    private selectedCriterias = new Map<string, boolean>();

    private destroy$: Subject<boolean> = new Subject<boolean>();

    constructor(
        private fb: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private electionCriteriaService: ElectionCriteriaService,
        public authService: AuthService,
        private dialog: MatDialog,
        private criteriaService: CriteriaService,
        private titleService: TitleService
    ) {
        this.titleService.setTitle('component.choose_criteria.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;
            this.criterias = data.criterias as Criteria[];

            this.election.electionCriterias.forEach((electionCriteria: ElectionCriteria) => {
                this.selectedCriterias.set((electionCriteria.criteria as Criteria)['@id'], true);
            });

            this.addElectionCriteriaFormControls();
        });
    }

    public onChange(criteriaId: string, event: Event): void {
        if ((event.target as HTMLInputElement).checked) {
            this.addCriteria(criteriaId, event);
        } else {
            this.removeCriteria(criteriaId, event);
        }
    }

    public isInElectionCriteria(criteria: Criteria): boolean {
        return this.selectedCriterias.has(criteria['@id']);
    }

    public createCriteria(): void {
        this.authService.isAuthenticated.pipe(
            map((response, index) => [response, index]), // passes also index parameter for each next
            tap(([isAuthenticated, index]) => {
                if (!isAuthenticated && index === 0) { // open dialog only once
                    this.openAuthenticationDialog();
                }
            }),
            takeUntil(this.destroy$)
        ).subscribe(([isAuthenticated]) => {
            if (isAuthenticated) {
                this.openCriteriaDialog();
            }
        });
    }

    public editCriteria(criteria: Criteria): void {
        const dialog = this.dialog.open(AddCriteriaDialogComponent, {
            minWidth: 400,
            maxWidth: 500,
            data: {
                election: this.election,
                criteria,
            }
        });

        dialog.afterClosed().subscribe((notCancelClicked) => {
            if (false !== notCancelClicked) {
                this.criteriaService.findAll(IriUtil.extractId(this.election['@id']))
                    .subscribe((criterias: Criteria[]) => this.criterias = criterias);
            }
        });
    }

    public save(): void {
        if (this.form.valid) {
            void this.router.navigate(['elections', IriUtil.extractId(this.election['@id']), 'assignments']);
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }

    public ngOnDestroy(): void {
        this.destroy$.next(true);
        this.destroy$.unsubscribe();
    }

    private addElectionCriteriaFormControls(): void {
        const criteriaFormArray = this.form.controls.criterias as FormArray;
        this.election.electionCriterias.forEach((electionCriteria: ElectionCriteria) => {
            criteriaFormArray.push(new FormGroup({
                criteria: new FormControl((electionCriteria.criteria as Criteria)['@id']),
                electionCriteria: new FormControl(electionCriteria['@id']),
            }));
        });
    }

    private addCriteria(criteriaId: string, event: Event): void {
        this.disable = true;
        const criteriaFormArray = this.form.controls.criterias as FormArray;

        this.electionCriteriaService.add({
            election: this.election['@id'],
            criteria: criteriaId,
        }).pipe(
            take(1),
            finalize(() => this.disable = false)
        ).subscribe((electionCriteria: ElectionCriteria) => {
            this.selectedCriterias.set(criteriaId, true);

            criteriaFormArray.push(new FormGroup({
                criteria: new FormControl(criteriaId),
                electionCriteria: new FormControl(electionCriteria['@id']),
            }));
        }, () => {
            (event.target as HTMLInputElement).checked = false;
        });
    }

    private removeCriteria(criteriaId: string, event: Event): void {
        this.disable = true;

        const criteriaFormArray = this.form.controls.criterias as FormArray;
        const index = criteriaFormArray.controls.findIndex((x: AbstractControl) => (x.value as ElectionCriteria).criteria === criteriaId);
        const formGroup = criteriaFormArray.at(index);

        this.selectedCriterias.delete(criteriaId);

        this.electionCriteriaService.remove((formGroup.value as {election: string; electionCriteria: string}).electionCriteria)
            .pipe(
                take(1),
                finalize(() => this.disable = false)
            ).subscribe(() => {
                criteriaFormArray.removeAt(index);
            }, () => {
                (event.target as HTMLInputElement).checked = true;
                this.selectedCriterias.set(criteriaId, true);
            });
    }

    private openAuthenticationDialog() {
        this.dialog.open(LoginDialogComponent, {
            minWidth: 400
        });
    }

    private openCriteriaDialog() {
        const dialog = this.dialog.open(AddCriteriaDialogComponent, {
            minWidth: 400,
            maxWidth: 500,
            data: {
                election: this.election
            }
        });
        dialog.afterClosed().subscribe((notCancelClicked) => {
            if (false !== notCancelClicked) {
                this.criteriaService.findAll(IriUtil.extractId(this.election['@id']))
                    .subscribe((criterias: Criteria[]) => this.criterias = criterias);
            }
        });
    }
}
