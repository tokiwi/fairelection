import { StatisticItemResource } from './statistic-item-resource.model';

export interface StatisticResource {
    '@id': string;
    '@type': string;
    rows: StatisticItemResource[];
}
