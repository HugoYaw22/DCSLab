const Ziggy = {"url":"http:\/\/localhost:8000","port":8000,"defaults":{},"routes":{"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirm":{"uri":"user\/confirm-password","methods":["GET","HEAD"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"api.auth":{"uri":"api\/auth","methods":["POST"]},"api.get.db.company.company.read":{"uri":"api\/get\/dashboard\/company\/company\/read","methods":["GET","HEAD"]},"api.get.db.company.company.default":{"uri":"api\/get\/dashboard\/company\/company\/default","methods":["GET","HEAD"]},"api.get.db.admin.users.read":{"uri":"api\/get\/dashboard\/admin\/users\/read","methods":["GET","HEAD"]},"api.get.db.admin.users.roles.read":{"uri":"api\/get\/dashboard\/admin\/users\/roles\/read","methods":["GET","HEAD"]},"api.get.db.core.profile.read":{"uri":"api\/get\/dashboard\/core\/profile\/read","methods":["GET","HEAD"]},"api.get.db.core.inbox.list.thread":{"uri":"api\/get\/dashboard\/core\/inbox\/list\/threads","methods":["GET","HEAD"]},"api.get.db.core.inbox.search.users":{"uri":"api\/get\/dashboard\/core\/inbox\/search\/users","methods":["GET","HEAD"]},"api.get.db.core.activity.route.list":{"uri":"api\/get\/dashboard\/core\/activity\/route\/list","methods":["GET","HEAD"]},"api.get.db.core.user_menu":{"uri":"api\/get\/dashboard\/core\/user\/menu","methods":["GET","HEAD"]},"api.get.db.common.ddl.list.countries":{"uri":"api\/get\/dashboard\/common\/ddl\/list\/countries","methods":["GET","HEAD"]},"api.get.db.common.ddl.list.statuses":{"uri":"api\/get\/dashboard\/common\/ddl\/list\/statuses","methods":["GET","HEAD"]},"api.post.db.admin.users.save":{"uri":"api\/post\/dashboard\/admin\/users\/save","methods":["POST"]},"api.post.db.admin.users.edit":{"uri":"api\/post\/dashboard\/admin\/users\/edit\/{id}","methods":["POST"]},"api.post.db.core.profile.update.profile":{"uri":"api\/post\/dashboard\/core\/profile\/update\/profile","methods":["POST"]},"api.post.db.core.profile.update.settings":{"uri":"api\/post\/dashboard\/core\/profile\/update\/settings","methods":["POST"]},"api.post.db.core.profile.update.roles":{"uri":"api\/post\/dashboard\/core\/profile\/update\/roles","methods":["POST"]},"api.post.db.core.profile.send_email_verification":{"uri":"api\/post\/dashboard\/core\/profile\/send\/verification","methods":["POST"]},"api.post.db.core.profile.change_password":{"uri":"api\/post\/dashboard\/core\/profile\/change\/password","methods":["POST"]},"api.post.db.core.inbox.save":{"uri":"api\/post\/dashboard\/core\/inbox\/save","methods":["POST"]},"api.post.db.core.inbox.edit":{"uri":"api\/post\/dashboard\/core\/inbox\/edit","methods":["POST"]},"api.post.db.core.activity.log_route":{"uri":"api\/post\/dashboard\/core\/activity\/log\/route","methods":["POST"]},"front":{"uri":"\/","methods":["GET","HEAD"]},"home":{"uri":"home","methods":["GET","HEAD"]},"db":{"uri":"dashboard","methods":["GET","HEAD"]},"verification.verify":{"uri":"email\/verify\/{id}\/{hash}","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
