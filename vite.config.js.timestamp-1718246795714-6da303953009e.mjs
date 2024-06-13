// vite.config.js
import { defineConfig } from "file:///C:/Users/Admin/Documents/Projects/robotec-admin/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/Admin/Documents/Projects/robotec-admin/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/Users/Admin/Documents/Projects/robotec-admin/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJSX from "file:///C:/Users/Admin/Documents/Projects/robotec-admin/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
import i18n from "file:///C:/Users/Admin/Documents/Projects/robotec-admin/node_modules/laravel-vue-i18n/dist/vite.mjs";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.js"
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    vueJSX(),
    i18n()
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxBZG1pblxcXFxEb2N1bWVudHNcXFxcUHJvamVjdHNcXFxccm9ib3RlYy1hZG1pblwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcQWRtaW5cXFxcRG9jdW1lbnRzXFxcXFByb2plY3RzXFxcXHJvYm90ZWMtYWRtaW5cXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzL0FkbWluL0RvY3VtZW50cy9Qcm9qZWN0cy9yb2JvdGVjLWFkbWluL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSdcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nXG5pbXBvcnQgdnVlIGZyb20gJ0B2aXRlanMvcGx1Z2luLXZ1ZSdcbmltcG9ydCB2dWVKU1ggZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlLWpzeCdcbmltcG9ydCBpMThuIGZyb20gJ2xhcmF2ZWwtdnVlLWkxOG4vdml0ZSc7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiAncmVzb3VyY2VzL2pzL2FwcC5qcycsXG4gICAgICAgIH0pLFxuXG4gICAgICAgIHZ1ZSh7XG4gICAgICAgICAgICB0ZW1wbGF0ZToge1xuICAgICAgICAgICAgICAgIHRyYW5zZm9ybUFzc2V0VXJsczoge1xuICAgICAgICAgICAgICAgICAgICBiYXNlOiBudWxsLFxuICAgICAgICAgICAgICAgICAgICBpbmNsdWRlQWJzb2x1dGU6IGZhbHNlLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9KSxcblxuICAgICAgICB2dWVKU1goKSxcblxuICAgICAgICBpMThuKCksXG4gICAgXSxcbn0pXG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQTJVLFNBQVMsb0JBQW9CO0FBQ3hXLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFDaEIsT0FBTyxZQUFZO0FBQ25CLE9BQU8sVUFBVTtBQUVqQixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsSUFDWCxDQUFDO0FBQUEsSUFFRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUVELE9BQU87QUFBQSxJQUVQLEtBQUs7QUFBQSxFQUNUO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
