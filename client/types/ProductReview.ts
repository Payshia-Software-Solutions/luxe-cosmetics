export interface ProductReview {
    id?: number;
    user: string;
    rating: number;
    title?: string;
    comment: string;
    date?: string;
    timestamp?: string;
    verified?: boolean;
    helpful?: number;
}