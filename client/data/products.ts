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

export const products: Product[] = [
  {
    id: '1',
    slug: 'natural-glow-serum',
    name: 'Natural Glow Serum',
    price: 49.99,
    rating: 4.8,
    review: 128,
    description: 'Our bestselling Natural Glow Serum is formulated with powerful antioxidants and natural ingredients to give your skin a radiant, healthy glow. This lightweight serum absorbs quickly and works for all skin types.',
    longDescription: `
      Transform your skincare routine with our Natural Glow Serum, a revolutionary formula designed to enhance your skin's natural radiance while providing deep nourishment and protection.

      This lightweight yet powerful serum combines the latest in skincare technology with natural ingredients to deliver visible results. The fast-absorbing formula penetrates deeply into the skin, working at a cellular level to improve texture, tone, and overall skin health.

      Perfect for all skin types, this serum can be used both morning and night as part of your daily skincare routine. Its non-comedogenic formula ensures it won't clog pores while delivering essential nutrients to your skin.
    `,
    benefits: [
      'Brightens and evens skin tone',
      'Reduces fine lines and wrinkles',
      'Hydrates and nourishes',
      'Protects against environmental damage'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '24 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Hyaluronic Acid, Vitamin C, Niacinamide, Peptides, Natural Extracts',
    images: [
      'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
      'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
      'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Transform your skin with our Natural Glow Serum. This lightweight, fast-absorbing formula combines powerful antioxidants and natural ingredients for a radiant, healthy complexion.',
    reviews: [
      {
        id: 1,
        user: 'Sarah M.',
        rating: 5,
        date: '2024-02-15',
        title: 'Amazing Results!',
        comment: 'I\'ve been using this serum for a month and my skin has never looked better. The glow is real!',
        verified: true,
        helpful: 45
      },
      {
        id: 2,
        user: 'Emily R.',
        rating: 4,
        date: '2024-02-10',
        title: 'Good but pricey',
        comment: 'The serum works well and I can see improvement in my skin texture. A bit expensive though.',
        verified: true,
        helpful: 32
      }
    ]
  },
  // New products under Serums category
  {
    id: '2',
    slug: 'vitamin-c-serum',
    name: 'Vitamin C Serum',
    price: 39.99,
    rating: 4.5,
    review: 105,
    description: 'A brightening Vitamin C serum that helps reduce the appearance of dark spots and promotes an even skin tone.',
    longDescription: `
      Infused with stabilized Vitamin C, this serum helps brighten the skin and even out the complexion. It also provides antioxidant protection, reducing the effects of free radicals on the skin.
      
      Perfect for daily use, this serum works best when applied in the morning for a glowing, radiant complexion. It also helps to improve skin elasticity and firmness over time.
    `,
    benefits: [
      'Brightens dark spots',
      'Evens out skin tone',
      'Improves skin elasticity',
      'Protects against environmental damage'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '18 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Vitamin C, Hyaluronic Acid, Aloe Vera, Green Tea Extract',
    images: [
      'https://images.unsplash.com/photo-1614974604976-106dff5c7b9b?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8OXx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080',
      'https://images.unsplash.com/photo-1571161159271-e59163de22c3?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8OXx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Brighten your skin and even out your complexion with our Vitamin C Serum. Packed with powerful antioxidants and soothing ingredients.',
    reviews: [
      {
        id: 1,
        user: 'John D.',
        rating: 5,
        date: '2024-03-01',
        title: 'Skin feels amazing!',
        comment: 'This serum has worked wonders on my skin, and I see fewer dark spots now!',
        verified: true,
        helpful: 22
      },
      {
        id: 2,
        user: 'Laura P.',
        rating: 4,
        date: '2024-03-03',
        title: 'Great results, but slightly sticky',
        comment: 'It works well, but I find it a little sticky at first.',
        verified: true,
        helpful: 18
      }
    ]
  },
  {
    id: '3',
    slug: 'hydrating-hyaluronic-serum',
    name: 'Hydrating Hyaluronic Serum',
    price: 44.99,
    rating: 4.7,
    review: 215,
    description: 'This intensely hydrating serum replenishes moisture levels and helps plump and smooth the skin.',
    longDescription: `
      Give your skin a moisture boost with this potent hyaluronic acid serum. It replenishes lost hydration, leaving the skin soft, smooth, and plump.
      
      This lightweight serum is perfect for dry and dehydrated skin, offering long-lasting hydration without feeling greasy. It can be used daily and is especially effective after cleansing or exfoliating your skin.
    `,
    benefits: [
      'Deeply hydrates skin',
      'Smooths and plumps the skin',
      'Reduces the appearance of fine lines',
      'Improves skin elasticity'
    ],
    specifications: {
      'Skin Type': 'Dry and Dehydrated Skin',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '18 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Hyaluronic Acid, Aloe Vera, Vitamin E, Glycerin',
    images: [
      'https://images.unsplash.com/photo-1601925033129-7e618634ab2a?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080',
      'https://images.unsplash.com/photo-1577755005327-cc77c3c9b8ff?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Hydrate your skin deeply with our Hyaluronic Serum. This serum replenishes moisture levels, leaving the skin smooth and plump.',
    reviews: [
      {
        id: 1,
        user: 'David F.',
        rating: 5,
        date: '2024-01-15',
        title: 'Hydration at its best!',
        comment: 'This serum leaves my skin so soft and hydrated. Highly recommend it!',
        verified: true,
        helpful: 75
      },
      {
        id: 2,
        user: 'Sophie W.',
        rating: 4,
        date: '2024-01-20',
        title: 'Very hydrating',
        comment: 'It works great, but I wish it absorbed a little faster.',
        verified: true,
        helpful: 53
      }
    ]
  }
];
