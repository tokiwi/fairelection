import { Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { FormUtil } from '../../../core/utils/form.util';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute, Router } from '@angular/router';
import { AssignmentResourceService } from '../../../core/services/assignment-resource.service';
import { IriUtil } from '../../../core/utils/iri.util';
import { TitleService } from '../../../core/services/title.service';
import { finalize, take, takeUntil } from 'rxjs/operators';
import { DragScrollComponent } from 'ngx-drag-scroll';
import { ElectionCriteria } from '../../../core/models/election-criteria.model';
import { DirtyFormWarnableComponent } from '../../../components/form-component/dirty-form.component';
import { ConfirmDialogService } from '../../../core/services/confirm-dialog.service';
import { Subject } from 'rxjs';

@Component({
    selector: 'app-criteria-assignment',
    templateUrl: './criteria-assignment.component.html',
    styleUrls: ['./criteria-assignment.component.scss']
})
export class CriteriaAssignmentComponent extends DirtyFormWarnableComponent implements OnInit, OnDestroy {
    @ViewChild('criterias', {read: DragScrollComponent, static: true}) ds: DragScrollComponent;

    public index = 0;
    public leftNavDisabled = false;
    public rightNavDisabled = false;

    public loading = false;
    public election: Election;
    public map = new Map<string, Map<string, number>>();

    public form = this.fb.group({
        numberOfPeopleToElect: [null, [Validators.required, Validators.min(1)]],
    });

    public numberOfPeopleToElect = 0;
    public carouselHeight = 600;

    private destroy$: Subject<boolean> = new Subject<boolean>();

    private readonly carouselWrapperHeight = 200;
    private readonly carouselItemHeight = 88;

    constructor(
        private fb: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private assignmentResourceService: AssignmentResourceService,
        private titleService: TitleService,
        private confirmDialogService: ConfirmDialogService
    ) {
        super();

        this.titleService.setTitle('component.criteria_assignment.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;
            this.form.patchValue(this.election);
            this.numberOfPeopleToElect = this.form.get('numberOfPeopleToElect').value as number;

            this.carouselHeight = this.computeCarouselHeight();
        });
    }

    ngOnInit(): void {
        this.form.get('numberOfPeopleToElect').valueChanges.subscribe((value: number) => {
            this.numberOfPeopleToElect = value;
        });

        this.confirmDialogService.getConfirmClickEmitter().pipe(
            takeUntil(this.destroy$)
        ).subscribe((confirm: boolean) => {
            // false = cancel, true = confirm
            if (!confirm) {
                this.save(true);
            }
        });
    }

    public save(avoidRedirect = false): void {
        const assignments = {};
        this.map.forEach((m: Map<string, number>) => {
            m.forEach((v: number, key: string) => {
                assignments[key] = v;
            });
        });

        if (this.form.valid) {
            this.loading = true;
            this.form.markAsPristine();

            const body = {
                election: this.election['@id'],
                numberOfPeopleToElect: +this.form.get('numberOfPeopleToElect').value,
                assignments
            };

            this.assignmentResourceService.save(body).pipe(
                take(1),
                finalize(() => this.loading = false)
            ).subscribe(() => {
                if (!avoidRedirect) {
                    void this.router.navigate(['elections', IriUtil.extractId(this.election['@id']), 'candidates']);
                }
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }

    public moveLeft(): void {
        this.ds.moveLeft();
    }

    public moveRight(): void {
        this.ds.moveRight();
    }

    public leftBoundStat(reachesLeftBound: boolean): void {
        this.leftNavDisabled = reachesLeftBound;
    }

    public rightBoundStat(reachesRightBound: boolean): void {
        this.rightNavDisabled = reachesRightBound;
    }

    public canLeave(): boolean {
        return !this.form.dirty;
    }

    public ngOnDestroy(): void {
        this.destroy$.next(true);
        this.destroy$.unsubscribe();
    }

    private computeCarouselHeight(): number {
        let max = 0;
        this.election.electionCriterias.forEach((electionCriteria: ElectionCriteria) => {
            max = electionCriteria.assignments.length > max ? electionCriteria.assignments.length : max;
        });

        return max * this.carouselItemHeight + this.carouselWrapperHeight;
    }
}
