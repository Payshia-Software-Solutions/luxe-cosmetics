export interface Product {
  id: string;
  slug: string;
  name: string;
  price: number;
  rating: number;
  review: number;
  description: string;
  longDescription: string;
  benefits: string[];
  specifications: Record<string, string>;
  ingredients: string;
  images: string[];
  category: string;
  breadcrumbs: string[];
  metaDescription: string;
  reviews: Review[];
}

export interface Review {
    id: number;
    user: string;
    rating: number;
    date: string;
    title: string;
    comment: string;
    verified: boolean;
    helpful: number;
  }