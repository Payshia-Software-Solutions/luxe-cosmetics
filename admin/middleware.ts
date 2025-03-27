// middleware.ts
import { NextResponse, NextRequest } from 'next/server';
import { getToken } from 'next-auth/jwt';

export async function middleware(req: NextRequest) {
    const session = await getToken({ req, secret: process.env.NEXTAUTH_SECRET });
    const { pathname } = req.nextUrl;

    const publicPaths = ['/login', '/api/auth'];
    const isPublicPage = publicPaths.some((path) => pathname.startsWith(path));

    if (!session && !isPublicPage) {
        return NextResponse.redirect(new URL('/login', req.url));
    }

    if (session && pathname === '/login') {
        return NextResponse.redirect(new URL('/', req.url));
    }

    return NextResponse.next();
}

export const config = {
    matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
};