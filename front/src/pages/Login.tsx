import React, {useContext, useState} from "react";
import {Link, useNavigate} from "react-router-dom";
import { login } from "../services/auth.ts";
import type {LoginPayload} from "../types/Auth/LoginPayload.ts";
import {AuthContext} from "../contexts/AuthContext.tsx";

export default function Login() {
    const [email, setEmail] = useState<string>("");
    const [password, setPassword] = useState<string>("");
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);
    const { storeUser } = useContext(AuthContext);

    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError(null);
        setLoading(true);

        const payload: LoginPayload = { email, password };

        try {
            const res = await login(payload);
            console.log("✅ Utilisateur connecté :", res);
            storeUser(res.user)
            navigate("/", { replace: true });
        } catch (err: any) {
            console.error("❌ Erreur de connexion :", err);
            setError(err?.message || "Erreur inconnue");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-base-200 flex items-center justify-center p-6">
            <div className="w-full max-w-5xl grid lg:grid-cols-[1.618fr_1fr] gap-12 items-center">
                <div className="hidden lg:flex flex-col justify-center space-y-6 text-left">
                    <h1 className="text-5xl font-extrabold leading-tight text-primary">
                        Bienvenue 👋
                    </h1>
                    <p className="text-lg text-gray-500 max-w-md">
                        Connectez-vous pour accéder à votre espace personnel et gérer vos projets
                        en toute simplicité.
                    </p>
                    <div className="divider w-24 border-primary"></div>
                    <p className="text-sm text-gray-400">
                        Vous n’avez pas encore de compte ?{" "}
                        <Link to="/register" className="link link-primary font-semibold">
                            Créez-en un ici
                        </Link>
                    </p>
                </div>
                <div className="card bg-base-100 w-full max-w-md shadow-xl p-8">
                    <h2 className="text-2xl font-bold text-center mb-6">Connexion</h2>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        {error && (
                            <div className="alert alert-error mb-4">
                                <span>{error}</span>
                            </div>
                        )}
                        <div className="form-control">
                            <label className="label">
                                <span className="label-text font-medium">Email</span>
                            </label>
                            <input
                                type="email"
                                placeholder="exemple@mail.com"
                                className="input input-bordered w-full"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                        </div>

                        <div className="form-control">
                            <label className="label">
                                <span className="label-text font-medium">Mot de passe</span>
                            </label>
                            <input
                                type="password"
                                placeholder="••••••••"
                                className="input input-bordered w-full"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                            />
                            <label className="label">
                                <a href="#" className="label-text-alt link link-hover">
                                    Mot de passe oublié ?
                                </a>
                            </label>
                        </div>

                        <button
                            type="submit"
                            className={`btn btn-primary  w-full mt-4 ${loading ? "loading" : ""}`}
                            disabled={loading}
                        >
                            {loading ? "Connexion..." : "Connexion"}
                        </button>

                        <p className="text-sm text-center mt-2 text-gray-500 lg:hidden">
                            Pas encore de compte ?{" "}
                            <Link to="/register" className="link link-primary">
                                Créez-en un
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    );
}
