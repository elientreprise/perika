import React from "react";

export default function Dashboard() {
    return (
        <div className="flex min-h-screen bg-base-200">
            <aside className="w-64 bg-base-100 shadow-lg p-5 flex flex-col justify-between">
                <div>
                    <h1 className="text-2xl font-bold mb-8 text-primary">Mon Tableau de Bord</h1>
                    <nav className="flex flex-col gap-3">
                        <a className="btn btn-ghost justify-start">🏠 Accueil</a>
                        <a className="btn btn-ghost justify-start">📦 Produits</a>
                        <a className="btn btn-ghost justify-start">👥 Utilisateurs</a>
                        <a className="btn btn-ghost justify-start">⚙️ Paramètres</a>
                    </nav>
                </div>
                <button className="btn btn-outline btn-error mt-10">Déconnexion</button>
            </aside>
            <main className="flex-1 p-10">
                <header className="flex items-center justify-between mb-10">
                    <div>
                        <h2 className="text-3xl font-bold text-primary">Bienvenue, Néo 👋</h2>
                        <p className="text-sm text-gray-500">Heureux de te revoir aujourd’hui.</p>
                    </div>
                    <div className="avatar placeholder">
                        <div className="bg-neutral text-neutral-content rounded-full w-12">
                            <span>N</span>
                        </div>
                    </div>
                </header>
                <section className="grid grid-cols-1 lg:grid-cols-[1.618fr_1fr] gap-8">
                    <div className="card bg-base-100 shadow-xl p-6">
                        <h3 className="text-xl font-semibold mb-4">Statistiques générales</h3>
                        <div className="stats shadow w-full">
                            <div className="stat">
                                <div className="stat-title">Utilisateurs</div>
                                <div className="stat-value text-primary">1,248</div>
                                <div className="stat-desc">↗︎ 12% ce mois</div>
                            </div>
                            <div className="stat">
                                <div className="stat-title">Ventes</div>
                                <div className="stat-value text-secondary">89</div>
                                <div className="stat-desc">↘︎ 3% cette semaine</div>
                            </div>
                            <div className="stat">
                                <div className="stat-title">Satisfaction</div>
                                <div className="stat-value text-accent">95%</div>
                                <div className="stat-desc">↗︎ +5%</div>
                            </div>
                        </div>
                    </div>
                    <div className="card bg-base-100 shadow-xl p-6">
                        <h3 className="text-xl font-semibold mb-4">Activité récente</h3>
                        <ul className="menu bg-base-100 rounded-box">
                            <li><a>✅ Nouveau produit ajouté</a></li>
                            <li><a>🧑‍💻 Nouvel utilisateur inscrit</a></li>
                            <li><a>💬 3 nouveaux messages</a></li>
                            <li><a>📦 Commande #152 expédiée</a></li>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    );
}
