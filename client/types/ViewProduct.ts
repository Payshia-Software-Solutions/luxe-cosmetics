export interface ProductSpecifications {
  ingredients?: string[];
  skin_type?: string[];
  [key: string]: string[] | string | undefined; 
}

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

export interface FormattedProduct {
  id: number;
  name: string;
  slug: string;
  category: string;
  price: number;
  rating: number;
  reviews: ProductReview[];
  description: string;
  longDescription: string;
  benefits: string[];
  specifications: ProductSpecifications;
  images: string[];
  breadcrumbs: string[];
}


