import React, { useState } from "react";
import { Link } from "react-router-dom";
import { register } from "../services/auth.ts";

export default function Register() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirm, setConfirm] = useState("");

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (password !== confirm) {
            alert("Les mots de passe ne correspondent pas");
            return;
        }
        const result = await register({ email, password });
        console.log(result);
    };

    return (
        <div className="min-h-screen bg-base-200 flex items-center justify-center p-6">
            <div className="w-full max-w-5xl grid lg:grid-cols-[1fr_1.618fr] gap-12 items-center">
                <div className="card bg-base-100 w-full max-w-md shadow-xl p-8 mx-auto">
                    <h2 className="text-2xl font-bold text-center mb-6">Créer un compte ✨</h2>
                    <form onSubmit={handleSubmit} className="space-y-4">
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
                        </div>

                        <div className="form-control">
                            <label className="label">
                                <span className="label-text font-medium">Confirmer le mot de passe</span>
                            </label>
                            <input
                                type="password"
                                placeholder="••••••••"
                                className="input input-bordered w-full"
                                value={confirm}
                                onChange={(e) => setConfirm(e.target.value)}
                                required
                            />
                        </div>

                        <button type="submit" className="btn btn-success w-full mt-4">
                            S’inscrire
                        </button>

                        <p className="text-sm text-center mt-2 text-gray-500">
                            Déjà un compte ?{" "}
                            <Link to="/login" className="link link-success">
                                Connectez-vous
                            </Link>
                        </p>
                    </form>
                </div>
                <div className="hidden lg:flex flex-col justify-center space-y-6 text-left">
                    <h1 className="text-5xl font-extrabold leading-tight text-success">
                        Rejoignez-nous 🌱
                    </h1>
                    <p className="text-lg text-gray-500 max-w-md">
                        Créez votre compte et commencez à explorer une expérience fluide et moderne,
                        pensée pour vous.
                    </p>
                    <div className="divider w-24 border-success"></div>
                    <p className="text-sm text-gray-400">
                        Vous avez déjà un compte ?{" "}
                        <Link to="/login" className="link link-success font-semibold">
                            Connectez-vous ici
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
