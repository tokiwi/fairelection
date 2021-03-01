export interface Paginator {
    '@context': string;
    '@id': string;
    '@type': string;
    'hydra:totalItems': number;
    'hydra:member': any[];
}
