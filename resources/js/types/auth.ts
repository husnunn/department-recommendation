export type User = {
    id: number;
    username: string;
    email: string | null;
    role: 'superadmin' | 'admin' | 'siswa';
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User | null;
};
